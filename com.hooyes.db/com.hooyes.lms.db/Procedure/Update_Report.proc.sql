DROP PROC [Update_Report]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-01-03
-- Update date: 2012-01-03
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Update_Report]
	@MID int = 0, 
	@Score int
AS
	IF EXISTS(SELECT * FROM Report WHERE MID = @MID)
		BEGIN
			UPDATE Report SET Score = @Score WHERE MID = @MID
		END
	ELSE
		BEGIN
			INSERT INTO Report(MID,Score) VALUES(@MID,@Score)
		END
RETURN 0