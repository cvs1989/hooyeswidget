-- DROP PROC [Task_ResetPaper]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-01-02
-- Update date: 2012-01-03
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Task_ResetPaper]
	@MID int = 0, 
	@Flag int = 0
AS
	DELETE FROM My_Question WHERE MID = @MID  and  Flag = @Flag
RETURN 0