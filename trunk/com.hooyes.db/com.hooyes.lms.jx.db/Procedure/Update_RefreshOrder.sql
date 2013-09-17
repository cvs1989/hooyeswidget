-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2013-09-16
-- Update date: 2013-09-16
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Update_RefreshOrder]
    @MID INT ,
    @OrderID INT ,
    @Code INT = 0 OUTPUT ,
    @Message NVARCHAR(4000) = '' OUTPUT
AS 
    DECLARE @Amount MONEY = 0 ,
        @Cash MONEY ,
        @Credit MONEY ,
        @B_Amount MONEY , /* B_  Balance Detail */
        @B_Cash MONEY ,
        @B_Rebate MONEY
    SELECT  @Amount = Amount
    FROM    Orders
    WHERE   MID = @MID
            AND ID = @OrderID
            AND [Status] < 10

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
		
    UPDATE  Orders
    SET     Cash = @Cash ,
            Credit = @Credit ,
            UpdateDate = GETDATE()
    WHERE   MID = @MID
            AND ID = @OrderID
    IF @@ROWCOUNT = 1 
        BEGIN
            SET @Code = 0
            SET @Message = 'success'
        END
    ELSE 
        BEGIN
            SET @Code = -1
            SET @Message = 'Error'
        END  
	 

    RETURN 0
