-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2013-09-24
-- Update date: 2013-09-24
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Update_Certificate] 
    @MID INT
AS 
    DECLARE @ID INT ,
        @BeginDate DATETIME ,
        @EndDate DATETIME

    
    SELECT  @BeginDate = MIN(CreateDate)
    FROM    dbo.Report
    WHERE   MID = @MID

    SELECT  @EndDate = MAX(UpdateDate)
    FROM    dbo.Report
    WHERE   MID = @MID

    UPDATE  [Certificate]
    SET     BeginDate = @BeginDate ,
            EndDate = @EndDate ,
            @ID = ID
    WHERE   MID = @MID
    IF @@ROWCOUNT = 0 
        BEGIN
            EXECUTE [Get_Seed] @ID = 5, @Value = @ID OUTPUT      
            INSERT  INTO [Certificate]
                    ( [ID] ,
                      [MID] ,
                      [BeginDate] ,
                      [EndDate] ,
                      [CreateDate]
                    )
            VALUES  ( @ID ,
                      @MID ,
                      @BeginDate ,
                      @EndDate ,
                      GETDATE()
                    )
        END  

    RETURN @ID
