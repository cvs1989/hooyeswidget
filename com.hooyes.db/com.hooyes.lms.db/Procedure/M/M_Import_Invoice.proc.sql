DROP PROC [M_Import_Invoice]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-07-12
-- Update date: 2012-07-14
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Import_Invoice]
	 @SN decimal = 0 output
	,@IDCard varchar(20)
	,@IDSN varchar(30)
	,@Name varchar(50)
	,@Amount money
	,@Title varchar(100)
	,@Tel varchar(20)
	,@Province varchar(10) = ''
	,@City varchar(10)	   = ''
	,@Address varchar(300)
	,@Zip varchar(10)
	,@Code int = 0 output
	,@Message varchar(200) = '' output
AS
    DECLARE @flag int = 0 
	DECLARE @IID int = 0
	SET @IDCard = LTRIM(RTRIM(@IDCard))
	SET @IDSN   = LTRIM(RTRIM(@IDSN))
	IF NOT EXISTS(SELECT * FROM InvoiceImport WHERE [IDSN] = @IDSN AND [IDCard] = @IDCard AND flag = 1)
	 AND NOT EXISTS(SELECT * FROM Invoice WHERE [IDSN] = @IDSN )
	BEGIN
		SET @flag = 0
		IF NOT EXISTS(SELECT * FROM Member WHERE [IDSN] = @IDSN AND [IDCard] = @IDCard)
			SET @flag = 3
		ELSE
		  BEGIN
		    EXECUTE [Get_Seed] 
			   @ID = 3
			  ,@Value = @IID OUTPUT
		  END


	END
	ELSE
	BEGIN
		SET @flag = 2

		SELECT @Code = 200,
			   @Message = 'EXISTS'+STR(@IDSN)
	END
	INSERT INTO [InvoiceImport]([IID],[SN] ,[IDCard],[IDSN],[Name],[Amount],[Title],[Tel],[Province],[City],[Address],[Zip],[flag])
		VALUES (@IID,@SN,@IDCard,@IDSN,@Name,@Amount,@Title,@Tel,@Province,@City,@Address,@Zip,@flag)

RETURN 0