-- ======================================
-- MySQL Triggers & Views for Carldan
-- ======================================
-- Run this script on your database to add:
-- 1. Stock deduction trigger (on order status -> 'Confirmed')
-- 2. Dashboard revenue view

-- ======================================
-- TRIGGER: Deduct Material Stocks on Order Confirmation
-- ======================================
DELIMITER $$

CREATE TRIGGER trg_deduct_stock_on_order_confirmed
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE product_id_var BIGINT;
    DECLARE qty_var INT;
    DECLARE material_id_var BIGINT;
    DECLARE required_qty_var INT;
    DECLARE stock_id_var BIGINT;
    DECLARE stock_qty BIGINT;
    DECLARE qty_to_deduct INT;
    DECLARE total_needed INT;
    
    DECLARE product_cursor CURSOR FOR
        SELECT op.product_id, op.quantity
        FROM order_product op
        WHERE op.order_id = NEW.id;
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    -- Only trigger when status changes to 'Confirmed'
    IF NEW.status = 'Confirmed' AND OLD.status != 'Confirmed' THEN
        OPEN product_cursor;
        
        read_products: LOOP
            FETCH product_cursor INTO product_id_var, qty_var;
            IF done THEN
                LEAVE read_products;
            END IF;
            
            -- For each material required by this product
            FOR material_row IN (
                SELECT mp.material_id, mp.required_quantity
                FROM material_product mp
                WHERE mp.product_id = product_id_var
            ) DO
                SET material_id_var = material_row.material_id;
                SET required_qty_var = material_row.required_quantity;
                SET total_needed = required_qty_var * qty_var;
                
                IF total_needed > 0 THEN
                    -- FIFO: Deduct from oldest stock batches first
                    FOR stock_row IN (
                        SELECT id, quantity
                        FROM stocks
                        WHERE material_id = material_id_var AND quantity > 0
                        ORDER BY created_at ASC
                    ) DO
                        SET stock_id_var = stock_row.id;
                        SET stock_qty = stock_row.quantity;
                        SET qty_to_deduct = LEAST(stock_qty, total_needed);
                        
                        -- Deduct quantity and increment stock_out
                        UPDATE stocks
                        SET quantity = quantity - qty_to_deduct,
                            stock_out = stock_out + qty_to_deduct
                        WHERE id = stock_id_var;
                        
                        SET total_needed = total_needed - qty_to_deduct;
                        
                        IF total_needed <= 0 THEN
                            LEAVE;
                        END IF;
                    END FOR;
                END IF;
            END FOR;
        END LOOP read_products;
        
        CLOSE product_cursor;
    END IF;
END$$

DELIMITER ;

-- ======================================
-- VIEW: Dashboard Total Revenue
-- ======================================
CREATE OR REPLACE VIEW vw_dashboard_revenue AS
SELECT 
    COUNT(DISTINCT o.id) as total_orders,
    SUM(op.quantity * op.price) as total_revenue,
    AVG(op.quantity * op.price) as avg_order_value,
    DATE(o.order_date) as order_date
FROM orders o
LEFT JOIN order_product op ON o.id = op.order_id
WHERE o.status = 'Confirmed'
GROUP BY DATE(o.order_date)
ORDER BY order_date DESC;

-- ======================================
-- VIEW: Overall Revenue Summary (All Time)
-- ======================================
CREATE OR REPLACE VIEW vw_revenue_summary AS
SELECT 
    COUNT(DISTINCT o.id) as total_confirmed_orders,
    IFNULL(SUM(op.quantity * op.price), 0) as total_lifetime_revenue,
    IFNULL(AVG(op.quantity * op.price), 0) as average_order_value
FROM orders o
LEFT JOIN order_product op ON o.id = op.order_id
WHERE o.status = 'Confirmed';

-- ======================================
-- VIEW: Material Stock Deduction History
-- ======================================
CREATE OR REPLACE VIEW vw_material_stock_history AS
SELECT 
    m.id,
    m.name as material_name,
    s.id as stock_batch_id,
    CONCAT('BATCH-', DATE_FORMAT(s.created_at, '%Y%m%d'), '-', s.id) as batch_number,
    s.stock_in as qty_received,
    s.stock_out as qty_used,
    s.quantity as qty_remaining,
    s.unit_cost,
    s.created_at as received_date,
    s.updated_at as last_modified,
    (s.stock_in - s.quantity) as qty_deducted
FROM stocks s
JOIN materials m ON s.material_id = m.id
ORDER BY m.id, s.created_at DESC;

-- ======================================
-- USAGE NOTES:
-- ======================================
-- 1. After running this script, update an order's status to 'Confirmed' 
--    and the trigger will automatically deduct materials (FIFO).
--
-- 2. Query the revenue views:
--    SELECT * FROM vw_dashboard_revenue;
--    SELECT * FROM vw_revenue_summary;
--    SELECT * FROM vw_material_stock_history;
--
-- 3. If you need to remove triggers/views:
--    DROP TRIGGER trg_deduct_stock_on_order_confirmed;
--    DROP VIEW vw_dashboard_revenue;
--    DROP VIEW vw_revenue_summary;
--    DROP VIEW vw_material_stock_history;
-- ======================================
