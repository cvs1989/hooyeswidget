DROP PROC [Update_Member]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-02-02
-- Update date: 2012-02-02
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Update_Member]
	 @MID int output 
	,@Name varchar(50) = ''
	,@IDCard varchar(20) 
	,@IDSN varchar(30) 
	,@Year int  = 2012
	,@Type int  = 0
	,@Level int = 0
AS
	IF @MID <= 0
	BEGIN
		DECLARE @SID int
		EXECUTE [Get_Seed] 
		   @ID = 1
		  ,@Value = @SID OUTPUT
		SET @MID = @SID
		INSERT INTO [Member]
			   ([MID]
			   ,[Name]
			   ,[IDCard]
			   ,[IDSN]
			   ,[Year]
			   ,[Type]
			   ,[Level])
		 VALUES
			   (@MID
			   ,@Name
			   ,@IDCard
			   ,@IDSN
			   ,@Year
			   ,@Type
			   ,@Level)
	END
	ELSE
	BEGIN
		UPDATE [Member] SET Name = @Name WHERE MID = @MID
	END
RETURN 0