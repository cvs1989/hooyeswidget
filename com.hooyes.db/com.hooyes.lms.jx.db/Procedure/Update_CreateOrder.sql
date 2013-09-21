-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2013-09-14
-- Update date: 2013-09-19
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Update_CreateOrder]
    @MID INT ,
    @Tags VARCHAR(100) ,     --- Products ID 
    @Memo VARCHAR(200) = NULL ,
    @OrderID VARCHAR(20) = '' OUTPUT ,
    @Code INT = 0 OUTPUT ,
    @Message NVARCHAR(4000) = '' OUTPUT
AS 
    DECLARE @ID INT = 0 ,
        @Amount MONEY ,
        @Cash MONEY ,
        @Credit MONEY ,
        @B_Amount MONEY , /* B_  Balance Detail */
        @B_Cash MONEY ,
        @B_Rebate MONEY

    SELECT  @ID = ID ,
            @OrderID = OrderID
    FROM    Orders
    WHERE   MID = @MID
            AND Tags = @Tags
            AND [Status] < 10
    IF @ID = 0 
        BEGIN
            EXECUTE [Get_Seed] @ID = 4, @Value = @ID OUTPUT
            /* @OrderID 产生逻辑   长度限制为16位*/

            SET @OrderID = CONVERT(VARCHAR(8), GETDATE(),112)  + CONVERT(VARCHAR(8), 1000000 + @ID)
            
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
                      [OrderID] ,
                      [Amount] ,
                      [Cash] ,
                      [Credit] ,
                      [Status] ,
                      [Tags] ,
                      [CreateDate] ,
                      [UpdateDate] ,
                      [Memo]
                    )
            VALUES  ( @ID ,
                      @MID ,
                      @OrderID ,
                      @Amount ,
                      @Cash ,
                      @Credit ,
                      1 ,
                      @Tags ,
                      GETDATE() ,
                      GETDATE() ,
                      @Memo
                    )

            SET @Code = 0
            SET @Message = 'success'   
        END
    ELSE 
        BEGIN
            SET @Code = 1
            SET @Message = 'refresh order'   
        END      
    RETURN @ID
