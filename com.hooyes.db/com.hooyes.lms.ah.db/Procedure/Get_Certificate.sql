-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2013-09-24
-- Update date: 2013-09-24
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_Certificate]
	@MID INT
AS
	SELECT * FROM [Certificate] WHERE MID = @MID
RETURN 0
