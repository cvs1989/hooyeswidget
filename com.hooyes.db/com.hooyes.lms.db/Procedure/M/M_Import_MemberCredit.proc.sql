DROP PROC [M_Import_MemberCredit]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-04-25
-- Update date: 2012-04-25
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Import_MemberCredit]
		 @Name varchar(50) =''
		,@IDCard varchar(20)
		,@IDSN	varchar(30)
		,@Year  int = 0
		,@Code int = 0 output
		,@Message varchar(200) = '' output
AS
	IF NOT EXISTS(SELECT * FROM MemberCredit WHERE [IDSN] = @IDSN AND [IDCard] = @IDCard)
	BEGIN
		INSERT INTO [MemberCredit]([Name],[IDCard],[IDSN],[Year]) 
		VALUES (@Name
			,@IDCard
			,@IDSN
			,@Year
			)
	END
	ELSE
	BEGIN
		SELECT @Code = 200,
			   @Message = 'EXISTS'+STR(@IDSN)
	END

RETURN 0