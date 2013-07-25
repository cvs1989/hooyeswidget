DROP PROC [S_Import_MemberCredit]
GO
-- =============================================
-- Version:     1.0.0.2
-- Author:		hooyes
-- Create date: 2012-07-14
-- Update date: 2013-07-25
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
		,@Phone varchar(20) = NULL
		,@Tag int = 100
		,@Token varchar(100) =''
		,@Code int = 0 output
		,@Message varchar(200) = '' output
AS
	DECLARE @flag int = 0,
			@Status int = 0,
			@S_Token varchar(100)
	SET @IDCard = LTRIM(RTRIM(@IDCard))
	SET @IDSN   = LTRIM(RTRIM(@IDSN))
	SET @S_Token = sys.fn_VarBinToHexStr(hashbytes('md5',@Token))

	IF @Tag = 0 
	BEGIN
       SET @Tag = 100  
    END  

	IF NOT EXISTS(SELECT * FROM MemberCredit WHERE [IDSN] = @IDSN AND [IDCard] = @IDCard AND tag = @Tag AND Token = @S_Token)
	BEGIN
		SELECT @Code = 0,
			@Message = 'success'
		SET @flag = 0

		-- 检查是否存在
		IF NOT EXISTS(SELECT * FROM Member WHERE [IDSN] = @IDSN AND [IDCard] = @IDCard)
			SET @flag = 3

	    -- 检查是否已学完
		SELECT @Status = r.Status 
		FROM Report r 
			inner join Member m on r.MID = m.MID 
		WHERE m.IDSN = @IDSN

		IF @Status = 1
		BEGIN
			SELECT @Code = 1,
				@Message = 'finish',
				@flag = 1
		END

		-- 加入队列
		INSERT INTO [MemberCredit]([SN],[Name],[IDCard],[IDSN],[Year],[sType],[Type],[Phone],[flag],[tag],[Token]) 
		VALUES (@SN
			,@Name
			,@IDCard
			,@IDSN
			,@Year
			,@sType
			,@Type
			,@Phone
			,@flag
			,@Tag
			,@S_Token
			)
	END
	ELSE
	BEGIN

		SELECT @Code = 5,
			   @Message = 'EXISTS'+CONVERT(varchar(20),@IDSN)

		SELECT @Status = r.Status FROM Report r 
			inner join Member m on r.MID = m.MID 
		WHERE m.IDSN = @IDSN

		IF @Status = 1
		BEGIN
			SELECT @Code = 1,
				@Message = 'finish'
		END
		
	END



RETURN 0