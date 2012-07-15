DROP PROC [S_Import_MemberCredit]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-07-14
-- Update date: 2012-07-14
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[S_Import_MemberCredit]
		 @SN decimal = 0
		,@Name varchar(50) = ''
		,@IDCard varchar(20)
		,@IDSN	varchar(30)
		,@Year  int = 0
		,@sType varchar(20)
		,@Type  int = 0
		,@Phone varchar(20)
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
			   @Message = 'EXISTS'+STR(@IDSN)
	END
	INSERT INTO [MemberCredit]([SN],[Name],[IDCard],[IDSN],[Year],[sType],[Type],[Phone],[flag],[tag]) 
	VALUES (@SN
		,@Name
		,@IDCard
		,@IDSN
		,@Year
		,@sType
		,@Type
		,@Phone
		,@flag
		,100
		)

RETURN 0