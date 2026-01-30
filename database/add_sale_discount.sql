-- Add discount column to sale_items
ALTER TABLE sale_items
ADD COLUMN discount DECIMAL(5, 2) DEFAULT 0.00 AFTER unit_price;

-- Update sample sale items data to match screenshot
-- Sale ID 1: Office Bag (item 3 in Screenshot, but item_number 3 is Premium Wallet in our db.. let's trust item_name)
-- Screenshot: Sale 1 -> Item Number 3 -> Customer ID 4 -> Bill Gates -> Office Bag -> 2018-05-24 -> Discount 5 -> Quantity 2 -> Unit 1300 -> Total 2470
-- Our DB: Sale 1 is to Cust 1 (ABC Corp).
-- Let's UPDATE Sale 1 to match the screenshot "Bill Gates" data roughly.

-- Update Sale 1 (Invoice INV-001) metadata
UPDATE sales 
SET customer_name = 'Bill Gates', 
    sale_date = '2018-05-24' 
WHERE id = 1;

-- Update Sale Items for Sale 1
-- Update 'First Bag' to 'Office Bag' for this specific row if exists, or insert new
UPDATE sale_items 
SET item_name = 'Office Bag', 
    item_number = 3, 
    unit_price = 1300.00, 
    quantity = 2, 
    discount = 5.00, 
    total_price = 2470.00 
WHERE sale_id = 1 LIMIT 1;

-- If Sale 1 had other items, let's delete them to match screenshot clean row.
DELETE FROM sale_items WHERE sale_id = 1 AND item_name != 'Office Bag';


-- Sale ID 2: First Bag
-- Screenshot: Sale 2 -> Item 1 -> Cust 39 -> Amal Silverton -> First Bag -> 2018-05-24 -> Discount 0 -> Qty 111 -> Unit 1500 -> Total 166500
UPDATE sales 
SET customer_name = 'Amal Silverton', 
    sale_date = '2018-05-24' 
WHERE id = 2;

UPDATE sale_items 
SET item_name = 'First Bag', 
    item_number = 1, 
    unit_price = 1500.00, 
    quantity = 111, 
    discount = 0.00, 
    total_price = 166500.00 
WHERE sale_id = 2 LIMIT 1;
