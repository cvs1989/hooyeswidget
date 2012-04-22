DROP VIEW [v_m_invoice]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-04-21
-- Update date: 2012-04-21
-- Desc:
-- =============================================
CREATE VIEW [dbo].[v_m_invoice]
AS
	SELECT 
		 M.Name
		,M.Type
		,M.Year
		,ID = row_number() OVER (order by I.ID asc)
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
		,I.CreateDate
	FROM Invoice I 
		inner join Member M on M.MID = I.MID