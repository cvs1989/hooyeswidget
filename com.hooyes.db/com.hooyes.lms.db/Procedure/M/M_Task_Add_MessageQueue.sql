DROP PROC [M_Task_Add_MessageQueue]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2013-01-27
-- Update date: 2013-01-28
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Task_Add_MessageQueue]
	@Message nvarchar(200) = ''
AS
    DECLARE @DayID int = CONVERT(VARCHAR(10),GETDATE(),112)
	INSERT INTO MessageQueue(MID,DayID,Phone,Message,Flag)
	SELECT a.MID,
		@DayID,
		a.Phone ,
		@Message,
		0
	FROM member a
		LEFT JOIN Report b ON a.mid = b.mid
	WHERE (b.Status = 0 OR  b.Status IS NULL)
		AND a.MID>10000
		AND a.Phone IS NOT NULL
		AND NOT EXISTS(
		 SELECT 1 FROM dbo.MessageQueue WHERE MID = a.MID AND DayID = @DayID
	)
RETURN 0
