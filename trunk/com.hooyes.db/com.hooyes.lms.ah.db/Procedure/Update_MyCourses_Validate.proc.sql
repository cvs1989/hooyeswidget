-- =============================================
-- Author:		hooyes
-- Create date: 2012-02-12
-- Update date: 2012-02-12
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Update_MyCourses_Validate]
	@MID int = 0, 
	@CID int,
	@Validate int
AS
	IF EXISTS( SELECT * FROM [My_Courses] 	 WHERE MID = @MID
		 and CID = @CID)
	BEGIN
		UPDATE [My_Courses]
		   SET 
			   Validate = @Validate
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
			   ,0
			   ,0
			   ,0
			   ,@Validate)
	END
RETURN 0