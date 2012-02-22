DROP PROC [Get_Invoice]
-- =============================================
-- Author:		hooyes
-- Create date: 2012-02-22
-- Update date: 2012-02-22
-- Desc:
-- =============================================
GO
CREATE PROCEDURE [dbo].[Get_Invoice]
	@MID int = 0 
AS
	SELECT * FROM Invoice WHERE MID = @MID
RETURN 0