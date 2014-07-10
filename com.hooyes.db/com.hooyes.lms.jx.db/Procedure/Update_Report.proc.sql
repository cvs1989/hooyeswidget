-- DROP PROC [Update_Report]
GO
-- =============================================
-- Version:     1.0.0.3
-- Author:		hooyes
-- Create date: 2012-01-03
-- Update date: 2014-06-30
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Update_Report]
    @MID INT = 0 ,
    @Year INT = 0 ,
    @Score INT = NULL ,
    @Compulsory DECIMAL = NULL ,
    @Elective DECIMAL = NULL ,
    @Status INT = NULL ,
    @Minutes DECIMAL = NULL ,
    @Memo VARCHAR(100) = NULL
AS 
    IF EXISTS ( SELECT  *
                FROM    Report
                WHERE   MID = @MID
				AND [Year] = @Year ) 
        BEGIN
            UPDATE  Report
            SET     Score = CASE WHEN 0 <= @Score
                                      OR Score IS NULL THEN @Score
                                 ELSE Score
                            END ,
                    [Minutes] = ISNULL(@Minutes, [Minutes]) ,
                    [Status] = ISNULL(@Status, [Status]) ,
                    Memo = ISNULL(@Memo, Memo) ,
                    UpdateDate = GETDATE() ,
                    CommitDate = CASE WHEN @Status = 1 THEN GETDATE()
                                      ELSE CommitDate
                                 END
            WHERE   MID = @MID
			AND [Year] = @Year
        END
    ELSE 
        BEGIN
            INSERT  INTO Report
                    ( MID ,
					  [Year],
                      Score ,
                      Compulsory ,
                      Elective ,
                      [Status] ,
                      [Minutes] ,
                      Memo
                    )
            VALUES  ( @MID ,
			          @Year,
                      @Score ,
                      @Compulsory ,
                      @Elective ,
                      @Status ,
                      @Minutes ,
                      @Memo
                    )
        END
    RETURN 0