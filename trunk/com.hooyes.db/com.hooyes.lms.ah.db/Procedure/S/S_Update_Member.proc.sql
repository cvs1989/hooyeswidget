-- DROP PROC [S_Update_Member]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-07-23
-- Update date: 2012-07-23
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[S_Update_Member]
	 @MID int output 
	,@Name varchar(50) = ''
	,@IDCard varchar(20) 
	,@IDSN varchar(30) 
	,@Year int  = 2012
	,@Type int  = -1
	,@Level int = -1
	,@Tag int = 100
AS
	IF @MID <= 0
	BEGIN
	IF EXISTS( SELECT * FROM [Member] WHERE IDSN = @IDSN )
	BEGIN
		SELECT @MID = MID FROM [Member] WHERE IDSN = @IDSN
	END
	ELSE
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
			   ,[Level]
			   ,[Tag])
		 VALUES
			   (@MID
			   ,@Name
			   ,@IDCard
			   ,@IDSN
			   ,@Year
			   ,@Type
			   ,@Level
			   ,@Tag)
	END
	END
	ELSE
	BEGIN
		UPDATE [Member] SET Name = @Name WHERE MID = @MID
	END
RETURN 0