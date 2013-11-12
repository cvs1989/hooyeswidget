-- DROP PROC [M_Update_Courses]
GO
-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2012-03-04
-- Update date: 2012-07-27
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Update_Courses]
	@MID int = 0, 
	@CID int
AS
	DECLARE @Minutes int
			,@Second decimal
			,@count int
	SELECT  @Minutes = Length *45
		   ,@Second = Length *45 * 60
	FROM Courses where CID = @CID
	IF EXISTS( SELECT * FROM [My_Courses] 	 WHERE MID = @MID
		 and CID = @CID)
	BEGIN
		UPDATE [My_Courses]
		   SET 
			   [Status] = 1
			   ,[Minutes] = @Minutes
			   ,[Second] = @Second
		 WHERE MID = @MID
			 and CID = @CID
	END
	ELSE
	BEGIN
		INSERT INTO [My_Courses]
			   ([MID]
			   ,[CID]
			   ,[Minutes]
			   ,[Second]
			   ,[Status]
			   ,[Validate])
		 VALUES
			   (@MID
			   ,@CID
			   ,@Minutes
			   ,@Second
			   ,1
			   ,1)
	END

	SELECT @count = COUNT(1) FROM Contents WHERE CID = @CID
	IF @count = 0 
		SET @count = 1
	INSERT INTO My_Contents
	SELECT 
		 MID = @MID
		,a.CID
		,a.CCID
		,Minutes = (@Minutes/@count) + a.CCID
		,Second = (@Second/@count)+(a.CCID*60)
		,Status = 1
		,LDate = GETDATE()
	FROM Contents a 
	WHERE a.CID = @CID
	AND NOT EXISTS(select 1 from My_Contents where MID = @MID and CID = a.CID and CCID = a.CCID)

	-- EXECUTE [Task_EvaluteCourses] @MID
RETURN 0