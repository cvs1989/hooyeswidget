DROP PROC [M_Get_InvoiceList]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-03-11
-- Update date: 2012-03-11
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Get_InvoiceList]
	
AS
	SELECT 
		 M.Name
		,M.Type
		,M.Year
		,ID = I.ID
		,[IID]
		,MID = I.MID
		,IDSN = I.IDSN
		,M.IDCard
		,NameContact = I.Name
		,Amount = CAST( I.[Amount] as decimal(10,0))
		,I.[Title]
		,I.[Tel]
		,I.[Province]
		,I.[City]
		,I.[Address]
		,I.[Zip]
	FROM Invoice I 
		inner join Member M on M.MID = I.MID

RETURN 0