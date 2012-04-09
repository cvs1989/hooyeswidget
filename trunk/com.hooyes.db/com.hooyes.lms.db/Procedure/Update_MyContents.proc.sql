DROP PROC [Update_MyContents]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2011-12-18
-- Update date: 2012-04-09
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Update_MyContents]
	@MID int = 0, 
	@CID int = 0,
	@CCID int = 0,
	@Minutes int = 0,
	@Second decimal = 0,
	@Status int =0
AS
	DECLARE @diff decimal 
			,@Code int
			,@Message varchar(200)
	IF @Minutes < 0 
		SET @Minutes = 0
	IF @Second < 0 
		SET @Second = 0

	/* 检查是否是欺诈 Code = 0 正常*/
	EXECUTE [Check_Fraud] 
			   @MID
			  ,@CID
			  ,@CCID
			  ,@Minutes
			  ,@Second
			  ,@Status
			  ,@Code OUTPUT
			  ,@Message OUTPUT

	IF @Code != 0 
	BEGIN
		SET @Minutes = 0
		SET @Second = 0 
		SET @Status = 0 
	END
		

	/* 时间校对 */
	SELECT @diff = DATEDIFF(SECOND,LDate,GETDATE())
	FROM [My_Contents] WHERE MID = @MID
		 and CID = @CID
		 and CCID = @CCID
	IF @Second > @diff 
	BEGIN
		SET @Message = STR(@CID) + ' ' + STR(@CCID) + ' ' + STR(@Second) + ' ' + STR(@diff) + ' '+ STR(@Status)
		SET @Second = @diff 
		EXECUTE SLog @MID, 101, @Message
	END


	IF EXISTS( SELECT * FROM [My_Contents] 	 WHERE MID = @MID
		 and CID = @CID
		 and CCID = @CCID )
	BEGIN
		UPDATE [My_Contents]
		   SET 
			   [Minutes] = [Minutes] + @Minutes
			  ,[Second]  = [Second]  + @Second
			  ,[Status] = CASE [Status] 
						  WHEN 1 THEN 1
						  ELSE @Status
						  END
		 WHERE MID = @MID
			 and CID = @CID
			 and CCID = @CCID
		UPDATE [My_Contents]
			SET [Minutes] = [Second]/60
			   ,[LDate] = GETDATE()
		 WHERE MID = @MID
			 and CID = @CID
			 and CCID = @CCID

	END
	ELSE
	BEGIN
	  IF @Second > 120
		 SET @Second = 120
	  SET @Minutes = @Second / 60
		INSERT INTO [My_Contents]
			   ([MID]
			   ,[CID]
			   ,[CCID]
			   ,[Minutes]
			   ,[Second]
			   ,[Status]
			   ,[LDate])
		 VALUES
			   (@MID
			   ,@CID
			   ,@CCID
			   ,@Minutes
			   ,@Second
			   ,@Status
			   ,GETDATE())
	END

	EXECUTE [Update_MyCourses] 
	   @MID    = @MID
	  ,@CID    = @CID
	  ,@Second  = @Second
	  ,@Status  = @Status

RETURN 0