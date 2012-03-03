DROP PROC [Get_Invoice]
-- =============================================
-- Author:		hooyes
-- Create date: 2012-02-22
-- Update date: 2012-03-03
-- Desc:
-- =============================================
GO
CREATE PROCEDURE [dbo].[Get_Invoice]
	@MID int = 0 
AS
	SELECT [ID]
		,[IID]
		,[MID]
		,[IDSN]
		,[Name]
		,[Amount] = CONVERT(decimal(10,0),[Amount])
		,[Title]
		,[Tel]
		,[Province]
		,[City]
		,[Address]
		,[Zip]
	FROM [dbo].[Invoice]
	WHERE MID = @MID
RETURN 0