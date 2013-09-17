-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2013-09-17
-- Update date: 2013-09-17
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Update_AddTransactions]
    @MID INT ,
    @Amount MONEY ,
    @Cate INT = 1 ,
    @Source VARCHAR(100) = NULL ,
    @Memo NVARCHAR(200) = NULL
AS 
    INSERT  INTO [Transactions]
            ( [MID] ,
              [Amount] ,
              [Cate] ,
              [Source] ,
              [Memo]
            )
    VALUES  ( @MID ,
              @Amount ,
              @Cate ,
              @Source ,
              @Memo
            )
    RETURN 0
