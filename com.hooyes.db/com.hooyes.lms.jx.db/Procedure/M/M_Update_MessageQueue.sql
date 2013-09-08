-- DROP PROC [M_Update_MessageQueue]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2013-01-27
-- Update date: 2013-01-27
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Update_MessageQueue]
	@MID int = 0,
	@DayID int = 0,
	@Flag int = 0
AS
	IF @DayID = 0 
		SET  @DayID  = CONVERT(VARCHAR(10),GETDATE(),112)
	UPDATE MessageQueue 
		SET Flag = @Flag
		   ,UpdateDate  = GETDATE()
    WHERE DayID = @DayID 
		AND MID = @MID
RETURN 0
