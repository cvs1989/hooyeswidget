DROP PROC [Check_Fraud]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-04-09
-- Update date: 2012-07-26
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Check_Fraud]
	@MID int = 0, 
	@CID int = 0,
	@CCID int = 0,
	@Minutes int = 0,
	@Second decimal = 0,
	@Status int =0,
	@Code int = 0 output,
	@Message varchar(200) = '' output
AS
	DECLARE @Is_Current int = 0
			,@diff decimal 
			,@DayID int = 0

	IF EXISTS(SELECT * FROM Member WHERE MID = @MID AND Year = 2012)
		SET @Is_Current = 1
	/* 2012 年学员,需要验证 Validate = 1 方可以计时 */
	IF @Is_Current = 1
	BEGIN
		IF NOT EXISTS(
			SELECT * FROM My_Courses WHERE MID = @MID and CID= @CID and Validate = 1
		)
		BEGIN
			SELECT @diff = DATEDIFF(SECOND,LDate,GETDATE())
			FROM [My_Contents] WHERE MID = @MID
				 and CID = @CID
				 and CCID = @CCID

			SET @Code = 100
			SET @Message = STR(@CID) + ' ' + STR(@CCID) + ' ' + STR(@Second) + ' '+ STR(@diff)+ ' '+ STR(@Status)
		END
			
	END

	/* 到当前时间 */

	IF @Code ! = 0 
	BEGIN
		EXECUTE SLog @MID, @Code, @Message
	END


RETURN 0