DROP PROC [M_Import_MemberCredit]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-04-25
-- Update date: 2012-07-23
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Import_MemberCredit]
		 @SN decimal = 0
		,@Name varchar(50) = ''
		,@IDCard varchar(20)
		,@IDSN	varchar(30)
		,@Year  int = 0
		,@Code int = 0 output
		,@Message varchar(200) = '' output
AS
	DECLARE @flag int = 0 
	SET @IDCard = LTRIM(RTRIM(@IDCard))
	SET @IDSN   = LTRIM(RTRIM(@IDSN))
	IF NOT EXISTS(SELECT * FROM MemberCredit WHERE [IDSN] = @IDSN AND [IDCard] = @IDCard AND flag = 1)
	BEGIN
		SET @flag = 0
		IF NOT EXISTS(SELECT * FROM Member WHERE [IDSN] = @IDSN AND [IDCard] = @IDCard)
			SET @flag = 3
	END
	ELSE
	BEGIN
		SET @flag = 2

		SELECT @Code = 200,
			   @Message = 'EXISTS'+CONVERT(varchar(20),@IDSN)
	END
	INSERT INTO [MemberCredit]([SN],[Name],[IDCard],[IDSN],[Year],[flag]) 
	VALUES (@SN
		,@Name
		,@IDCard
		,@IDSN
		,@Year
		,@flag
		)

RETURN 0