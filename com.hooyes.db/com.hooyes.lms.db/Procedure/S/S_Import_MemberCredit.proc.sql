DROP PROC [S_Import_MemberCredit]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-07-14
-- Update date: 2012-07-23
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
	DECLARE @flag int = 0,
			@Status int = 0
	SET @IDCard = LTRIM(RTRIM(@IDCard))
	SET @IDSN   = LTRIM(RTRIM(@IDSN))
	IF NOT EXISTS(SELECT * FROM MemberCredit WHERE [IDSN] = @IDSN AND [IDCard] = @IDCard AND tag = 100)
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
	END
	ELSE
	BEGIN

		SELECT @Code = 200,
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