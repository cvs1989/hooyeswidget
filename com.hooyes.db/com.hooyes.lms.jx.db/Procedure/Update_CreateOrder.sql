-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2013-09-14
-- Update date: 2013-09-16
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Update_CreateOrder]
    @MID INT ,
    @Tags VARCHAR(100) ,     --- Products ID 
    @Memo VARCHAR(200) = NULL
AS 
    DECLARE @OrderID INT = 0 ,
        @Amount MONEY ,
        @Cash MONEY ,
        @Credit MONEY ,
        @B_Amount MONEY , /* B_  Balance Detail */
        @B_Cash MONEY ,
        @B_Rebate MONEY

    SELECT  @OrderID = ID
    FROM    Orders
    WHERE   MID = @MID
            AND Tags = @Tags
            AND [Status] < 10
    IF @OrderID = 0 
        BEGIN
            EXECUTE [Get_Seed] @ID = 4, @Value = @OrderID OUTPUT
            SELECT  @Amount = ISNULL(SUM(Price), 0)
            FROM    Products
            WHERE   ID IN ( SELECT  value
                            FROM    dbo.split(@Tags, ',') )

            EXECUTE [Get_Balance] @MID = @MID, @Amount = @B_Amount OUTPUT,
                @Cash = @B_Cash OUTPUT, @Rebate = @B_Rebate OUTPUT
  
    /* 余额优先抵扣 */
            SET @Cash = @Amount
            SET @Credit = 0
            IF @B_Amount > 0 
                BEGIN
                    IF @B_Amount > @Amount 
                        BEGIN
                            SET @Credit = @Amount  
                            SET @Cash = 0   
                        END   
                    ELSE 
                        BEGIN
                            SET @Credit = @B_Amount  
                            SET @Cash = @Amount - @Credit	   
                        END  
                END      

    
            INSERT  INTO [dbo].[Orders]
                    ( [ID] ,
                      [MID] ,
                      [Amount] ,
                      [Cash] ,
                      [Credit] ,
                      [Status] ,
                      [Tags] ,
                      [CreateDate] ,
                      [UpdateDate] ,
                      [Memo]
                    )
            VALUES  ( @OrderID ,
                      @MID ,
                      @Amount ,
                      @Cash ,
                      @Credit ,
                      1 ,
                      @Tags ,
                      GETDATE() ,
                      GETDATE() ,
                      @Memo
                    )
        END
    RETURN @OrderID
