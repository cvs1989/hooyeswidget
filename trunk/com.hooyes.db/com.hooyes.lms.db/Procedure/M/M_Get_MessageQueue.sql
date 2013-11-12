-- DROP PROC [M_Get_MessageQueue]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2013-01-27
-- Update date: 2013-01-28
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Get_MessageQueue]
	@DayID int = 0,
	@Flag int = 0,
	@Rows int = 10
AS
	IF @DayID = 0 
		SELECT TOP(@Rows) * FROM MessageQueue WHERE Flag = @Flag 
	ELSE
        SELECT TOP(@Rows) * FROM MessageQueue WHERE Flag = @Flag AND DayID = @DayID
RETURN 0
