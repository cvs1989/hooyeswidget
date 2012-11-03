DROP PROC [M_Check_Login]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-11-03
-- Update date: 2012-11-03
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Check_Login]
	 @LoginID varchar(50)
	,@LoginPWD varchar(50)
	,@Code int = 0 output
	,@Message varchar(200) = '' output
AS
	DECLARE @AID int,
			@Tag varchar(20)
	SELECT @AID = AID,
		   @Tag = Tag
	FROM [Hcms_Admin] 
	WHERE [Login] = @LoginID 
	and [Password] = sys.fn_VarBinToHexStr(hashbytes('md5',@LoginPWD+'hcms'+convert(varchar,AID)))
	IF @AID is not null
	BEGIN
		SET @Code = 0
		SET @Message = @Tag
	END
	ELSE
	BEGIN
		IF EXISTS(SELECT * FROM [Hcms_Admin] WHERE [Login] = @LoginID)
		BEGIN
			SET @Code = 201
			SET @Message = 'LoginID or LoginPWD incorrect'
		END
		ELSE
		BEGIN
			SET @Code = 200
			SET @Message = 'LoginID not exists'
		END
		SET @AID = -1
	END

RETURN @AID