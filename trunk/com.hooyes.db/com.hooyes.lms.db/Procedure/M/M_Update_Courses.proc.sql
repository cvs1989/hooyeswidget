-- DROP PROC [M_Update_Courses]
GO
-- =============================================
-- Version:     1.0.0.2
-- Author:		hooyes
-- Create date: 2012-03-04
-- Update date: 2014-01-06
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Update_Courses] @MID INT = 0, @CID INT
AS 
    DECLARE @Minutes INT ,
        @Second DECIMAL ,
        @count INT
    SELECT  @Minutes = Length * 45 ,
            @Second = Length * 45 * 60
    FROM    Courses
    WHERE   CID = @CID
    IF EXISTS ( SELECT  *
                FROM    [My_Courses]
                WHERE   MID = @MID
                        AND CID = @CID ) 
        BEGIN
            UPDATE  [My_Courses]
            SET     [Status] = 1 ,
                    [Minutes] = @Minutes ,
                    [Second] = @Second
            WHERE   MID = @MID
                    AND CID = @CID
        END
    ELSE 
        BEGIN
            INSERT  INTO [My_Courses]
                    ( [MID] ,
                      [CID] ,
                      [Minutes] ,
                      [Second] ,
                      [Status] ,
                      [Validate]
                    )
            VALUES  ( @MID ,
                      @CID ,
                      @Minutes ,
                      @Second ,
                      1 ,
                      1
                    )
        END

    SELECT  @count = COUNT(1)
    FROM    ( SELECT    cs.CID ,
                        ct.[CCID] ,
                        ct.[CCName] ,
                        ct.[Name] ,
                        ct.[Url]
              FROM      dbo.Contents ct
                        INNER JOIN dbo.Courses cs ON cs.CName = ct.CName
              WHERE     cs.CID = @CID
            ) AS a
    WHERE   CID = @CID
    IF @count = 0 
        SET @count = 1
    INSERT  INTO My_Contents
            SELECT  MID = @MID ,
                    a.CID ,
                    a.CCID ,
                    Minutes = ( @Minutes / @count ) + a.CCID ,
                    Second = ( @Second / @count ) + ( a.CCID * 60 ) ,
                    Status = 1 ,
                    LDate = GETDATE()
            FROM    ( SELECT    cs.CID ,
                                ct.[CCID] ,
                                ct.[CCName] ,
                                ct.[Name] ,
                                ct.[Url]
                      FROM      dbo.Contents ct
                                INNER JOIN dbo.Courses cs ON cs.CName = ct.CName
                      WHERE     cs.CID = @CID
                    ) AS a
            WHERE   a.CID = @CID
                    AND NOT EXISTS ( SELECT 1
                                     FROM   My_Contents
                                     WHERE  MID = @MID
                                            AND CID = a.CID
                                            AND CCID = a.CCID )

	-- EXECUTE [Task_EvaluteCourses] @MID
    RETURN 0