-- =============================================
-- Add image column to items table
-- =============================================

USE smart_billing;

-- Add image column to items table
ALTER TABLE items 
ADD COLUMN image VARCHAR(255) NULL AFTER quantity;

-- Update existing records with NULL (no image)
-- The application will handle showing a placeholder for NULL images
