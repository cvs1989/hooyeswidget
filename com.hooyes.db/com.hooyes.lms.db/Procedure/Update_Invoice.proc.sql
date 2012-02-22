DROP PROC [Update_Invoice]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2011-12-25
-- Update date: 2012-02-18
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Update_Invoice]
	 @IID int = 0 output
	,@MID int
	,@IDSN varchar(30)
	,@Name varchar(50)
	,@Amount money
	,@Title varchar(100)
	,@Tel varchar(20)
	,@Province varchar(10) = ''
	,@City varchar(10)	   = ''
	,@Address varchar(300)
	,@Zip varchar(10)
AS
	IF EXISTS(SELECT * FROM Invoice WHERE MID = @MID)
	BEGIN
		UPDATE [Invoice]
		SET 
			 [Name] = @Name 
			,[Amount] = @Amount
			,[Title] = @Title 
			,[Tel] = @Tel 
			,[Province] = @Province 
			,[City] = @City 
			,[Address] = @Address 
			,[Zip] = @Zip 
		WHERE MID = @MID
	END
	ELSE
	BEGIN
		DECLARE @SID int
		EXECUTE [Get_Seed] 
		   @ID = 3
		  ,@Value = @SID OUTPUT
		SET @IID = @SID
		INSERT INTO [Invoice]([IID] ,[MID],[IDSN],[Name],[Amount],[Title],[Tel],[Province],[City],[Address],[Zip])
		       VALUES (@IID,@MID,@IDSN,@Name,@Amount,@Title,@Tel,@Province,@City,@Address,@Zip)
	END
RETURN 0