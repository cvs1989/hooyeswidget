DROP PROC [Update_MyCourses]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2011-12-18
-- Update date: 2011-12-19
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Update_MyCourses]
	@MID int = 0, 
	@CID int = 0,
	@Second decimal = 0,
	@Status int =0
AS
	DECLARE	@Minutes int = 0
	IF EXISTS( SELECT * FROM [My_Courses] 	 WHERE MID = @MID
		 and CID = @CID)
	BEGIN
		UPDATE [My_Courses]
		   SET 
			   [Second]  = [Second]  + @Second
			  ,[Status] = @Status
		 WHERE MID = @MID
			 and CID = @CID
		UPDATE [My_Courses]
			SET [Minutes] = [Second]/60
		WHERE MID = @MID
			and CID = @CID
	END
	ELSE
	BEGIN
		SET @Minutes = @Second / 60
		INSERT INTO [My_Courses]
			   ([MID]
			   ,[CID]
			   ,[Minutes]
			   ,[Second]
			   ,[Status])
		 VALUES
			   (@MID
			   ,@CID
			   ,@Minutes
			   ,@Second
			   ,@Status)
	END
RETURN 0