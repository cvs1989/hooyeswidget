DROP PROC [M_Get_InvoiceListCount]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-04-22
-- Update date: 2012-04-22
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Get_InvoiceListCount]
AS
		SELECT COUNT(1) FROM v_m_invoice 
RETURN 0