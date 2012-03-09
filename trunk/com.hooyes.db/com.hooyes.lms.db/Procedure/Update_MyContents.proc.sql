DROP PROC [Update_MyContents]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2011-12-18
-- Update date: 2012-03-06
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
	IF @Minutes < 0 
		SET @Minutes = 0
	IF @Second < 0 
		SET @Second = 0

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
		 WHERE MID = @MID
			 and CID = @CID
			 and CCID = @CCID

	END
	ELSE
	BEGIN
	  SET @Minutes = @Second / 60
		INSERT INTO [My_Contents]
			   ([MID]
			   ,[CID]
			   ,[CCID]
			   ,[Minutes]
			   ,[Second]
			   ,[Status])
		 VALUES
			   (@MID
			   ,@CID
			   ,@CCID
			   ,@Minutes
			   ,@Second
			   ,@Status)
	END

	EXECUTE [Update_MyCourses] 
	   @MID    = @MID
	  ,@CID    = @CID
	  ,@Second  = @Second
	  ,@Status  = @Status

RETURN 0