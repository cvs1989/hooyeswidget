-- DROP PROC [Task_EvalutePaper]
GO
-- =============================================
-- Version:     1.0.0.4
-- Author:		hooyes
-- Create date: 2012-01-02
-- Update date: 2013-09-25
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Task_EvalutePaper] 
    @MID INT = 0, 
	@Year INT = 0
AS /* 1.判题是否正确 */
    DECLARE @QID INT ,
        @Answer NVARCHAR(50)
    DECLARE CusCursor CURSOR LOCAL STATIC
    FOR
        SELECT  QID ,
                Answer
        FROM    MY_Question
        WHERE   MID = @MID
    OPEN CusCursor 
    FETCH NEXT FROM CusCursor INTO @QID, @Answer
    WHILE ( @@FETCH_STATUS = 0 ) 
        BEGIN
            IF EXISTS ( SELECT  *
                        FROM    Question
                        WHERE   QID = @QID
                                AND Answer = @Answer ) 
                BEGIN
                    UPDATE  My_Question
                    SET     Status = 1
                    WHERE   QID = @QID
                            AND MID = @MID 
                END
            ELSE 
                BEGIN
                    UPDATE  My_Question
                    SET     Status = 0
                    WHERE   QID = @QID
                            AND MID = @MID 
                END

            FETCH NEXT FROM CusCursor INTO @QID, @Answer
        END
    CLOSE CusCursor 
    DEALLOCATE CusCursor 
	/* 2. 评分 */
    DECLARE @a FLOAT
    DECLARE @b FLOAT
    DECLARE @c FLOAT
    DECLARE @Score INT
    SELECT  @a = COUNT(status)
    FROM    dbo.My_Question
    WHERE   MID = @MID
            AND Status = 0
    SELECT  @b = COUNT(status)
    FROM    dbo.My_Question
    WHERE   MID = @MID
            AND Status = 1
    IF ( @a + @b ) > 0 
        BEGIN
            SET @c = @b / ( @a + @b ) 
            SET @Score = @c * 100
            EXECUTE [Update_Report] @MID = @MID, @Year = @Year,
                @Score = @Score
        END
    ELSE 
        BEGIN
            INSERT  INTO [Log]
                    ( [MID], [Code], [Message] )
            VALUES  ( @MID, 1001, 'a+b=0' )
        END  
	  


    RETURN 0