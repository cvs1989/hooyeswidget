-- =============================================
-- Version:     1.0.0.2
-- Author:		hooyes
-- Create date: 2013-09-26
-- Update date: 2013-09-30
-- Desc: 长度 13 
-- =============================================
CREATE PROCEDURE [dbo].[Task_CreateCards]
    @Amount MONEY = 0 ,
    @Count INT = 0 ,
    @Tag VARCHAR(50) = ''
AS 
    DECLARE @i INT = 0 ,
        @SN VARCHAR(50) = '' ,
        @Now DATETIME
    SET @Now = GETDATE()  
    WHILE ( @i < @Count
            AND @Count > 0
            AND @Amount > 0
          ) 
        BEGIN
            SELECT  @SN =CONVERT(varchar(13), CONVERT(DECIMAL,ROUND(RAND() * 10000000000000, 0)))
            IF NOT EXISTS ( SELECT  1
                            FROM    [Cards]
                            WHERE   SN = @SN ) 
				AND LEN(@SN) = 13
                BEGIN
                    INSERT  INTO [Cards]
                            ( [SN] ,
                              [Amount] ,
                              [Status] ,
                              [CreateDate] ,
                              [Tag]
                            )
                    VALUES  ( @SN ,
                              @Amount ,
                              1 ,
                              @Now ,
                              @Tag
		                    )
                    SET @i = @i + 1
                END
        END  
    RETURN 0
