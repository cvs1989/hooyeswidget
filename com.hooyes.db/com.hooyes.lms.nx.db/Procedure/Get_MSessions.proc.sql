-- =============================================
-- Author:		hooyes
-- Create date: 2012-02-04
-- Update date: 2012-02-04
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_MSessions]
	@MID int = 0
AS
	SELECT * FROM MSessions WHERE MID = @MID
RETURN 0