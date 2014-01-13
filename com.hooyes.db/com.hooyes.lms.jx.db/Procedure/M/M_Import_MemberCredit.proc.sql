-- DROP PROC [M_Import_MemberCredit]
GO
-- =============================================
-- Verion:      1.0.0.1
-- Author:		hooyes
-- Create date: 2012-04-25
-- Update date: 2014-01-10
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Import_MemberCredit]
    @SN DECIMAL = 0 ,
    @Name VARCHAR(50) = '' ,
    @IDCard VARCHAR(20) ,
    @IDSN VARCHAR(30) = '0' ,
    @Year INT = 0 ,
    @Code INT = 0 OUTPUT ,
    @Message VARCHAR(200) = '' OUTPUT
AS 
    DECLARE @flag INT = 0 
    SET @IDCard = LTRIM(RTRIM(@IDCard))
    
    IF NOT EXISTS ( SELECT  *
                    FROM    MemberCredit
                    WHERE   [Year] = @Year
                            AND [IDCard] = @IDCard 
							AND [SN] = @SN
							) 
        BEGIN
            SET @flag = 0
            IF NOT EXISTS ( SELECT  *
                            FROM    Member
                            WHERE   [Login] = @IDCard ) 
                SET @flag = 3

            INSERT  INTO [MemberCredit]
                    ( [SN] ,
                      [Name] ,
                      [IDCard] ,
                      [IDSN] ,
                      [Year] ,
                      [flag]
                    )
            VALUES  ( @SN ,
                      @Name ,
                      @IDCard ,
                      @IDSN ,
                      @Year ,
                      @flag
		            )

        END
    ELSE 
        BEGIN
            SET @flag = 2

            SELECT  @Code = 200 ,
                    @Message = 'EXISTS' + CONVERT(VARCHAR(20), @IDCard)
        END


    RETURN 0