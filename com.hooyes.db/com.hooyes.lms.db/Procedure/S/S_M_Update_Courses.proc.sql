DROP PROC [S_M_Update_Courses]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-07-23
-- Update date: 2012-07-23
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[S_M_Update_Courses]
	@MID int = 0, 
	@CID int
AS
	DECLARE @Minutes int
			,@Second decimal
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

	--EXECUTE [Task_EvaluteCourses] @MID
RETURN 0