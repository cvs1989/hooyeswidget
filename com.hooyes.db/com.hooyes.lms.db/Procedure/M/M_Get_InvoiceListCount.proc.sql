DROP PROC [M_Get_InvoiceListCount]
GO
-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2012-04-22
-- Update date: 2013-06-16
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Get_InvoiceListCount]
    @Filter VARCHAR(700) = ''
AS 
    IF @Filter = '' 
        SELECT  COUNT(1)
        FROM    v_m_invoice 
    ELSE 
        BEGIN
            DECLARE @SQL VARCHAR(800)
            SET @SQL = 'SELECT COUNT(1) FROM v_m_invoice WHERE ' + @Filter
            EXECUTE(@SQL)
        END
    RETURN 0