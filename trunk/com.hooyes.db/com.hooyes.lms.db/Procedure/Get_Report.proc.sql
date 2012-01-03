DROP PROC [Get_Report]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-01-03
-- Update date: 2012-01-03
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_Report]
	@MID int = 0
AS
	SELECT * FROM Report WHERE MID = @MID
RETURN 0