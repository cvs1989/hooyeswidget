-- DROP PROC [Get_Invoice]
-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2012-02-22
-- Update date: 2013-09-26
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_Invoice]
	@MID int = 0 
AS
	SELECT [ID]
		,[IID]
		,[MID]
		,[IDSN]
		,[Name]
		,[Amount] 
		,[Title]
		,[Tel]
		,[Province]
		,[City]
		,[Address]
		,[Zip]
		,[CreateDate]
	FROM [dbo].[Invoice]
	WHERE MID = @MID
	ORDER BY IID DESC
RETURN 0