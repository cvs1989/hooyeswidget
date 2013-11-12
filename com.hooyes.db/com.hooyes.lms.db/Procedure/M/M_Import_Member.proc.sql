-- DROP PROC [M_Import_Member]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-04-23
-- Update date: 2012-07-23
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Import_Member]
		 @Name varchar(50)
		,@IDCard varchar(20)
		,@IDSN	varchar(30)
		,@Year  int
		,@sType varchar(20) = ''
		,@Type  int         =100
		,@Phone varchar(50) =''
		,@Code int = 0 output
		,@Message varchar(200) = '' output
AS
	IF NOT EXISTS(SELECT * FROM Member WHERE [IDSN] = @IDSN AND [IDCard] = @IDCard)
	BEGIN
		INSERT INTO [MemberImport]([Name],[IDCard],[IDSN],[Year],[sType],[Type],[Phone]) 
		VALUES (@Name
			,@IDCard
			,@IDSN
			,@Year
			,@sType
			,@Type
			,@Phone
			)
	END
	ELSE
	BEGIN
		SELECT @Code = 200,
			   @Message = 'EXISTS'+CONVERT(varchar(20),@IDSN)
	END

RETURN 0