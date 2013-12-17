-- =============================================
-- Version:     1.0.0.4
-- Author:		hooyes
-- Create date: 2011-12-18
-- Update date: 2013-10-05
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Update_MyCourses]
    @MID INT = 0 ,
    @CID INT = 0 ,
    @Second DECIMAL = 0 ,
    @Status INT = 0
AS 
    DECLARE @Minutes INT = 0 ,
        @Year INT = 0
    IF @Second < 0 
        SET @Second = 0
    UPDATE  [My_Courses]
    SET     [Second] = [Second] + @Second ,
            [Minutes] = ( [Second] + @Second ) / 60
    WHERE   MID = @MID
            AND CID = @CID
    IF @@ROWCOUNT = 0 
        BEGIN
            SET @Minutes = @Second / 60
            INSERT  INTO [My_Courses]
                    ( [MID] ,
                      [CID] ,
                      [Minutes] ,
                      [Second] ,
                      [Status]
                    )
            VALUES  ( @MID ,
                      @CID ,
                      @Minutes ,
                      @Second ,
                      0
                    )
        END
  
    SELECT  @Year = [Year]
    FROM    Courses
    WHERE   CID = @CID  

	EXECUTE Task_EvaluteContents @MID,@CID

    EXECUTE Task_EvaluteCourses @MID, @Year

    RETURN 0