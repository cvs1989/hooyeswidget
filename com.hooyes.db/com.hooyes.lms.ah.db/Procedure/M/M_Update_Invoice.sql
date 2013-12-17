-- DROP PROC [M_Update_Invoice]
GO
-- =============================================
-- Version: 1.0.0.2
-- Author:		hooyes
-- Create date: 2013-06-18
-- Update date: 2013-10-03
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Update_Invoice]
    @IID INT = 0 OUTPUT ,
    @MID INT ,
    @IDSN VARCHAR(30) = NULL ,
    @Name VARCHAR(50) ,
    @Amount MONEY ,
    @Title VARCHAR(100) ,
    @Tel VARCHAR(20) ,
    @Province VARCHAR(10) = '' ,
    @City VARCHAR(10) = '' ,
    @Address VARCHAR(300) ,
    @Zip VARCHAR(10)
AS 
    IF EXISTS ( SELECT  *
                FROM    Invoice
                WHERE   MID = @MID 
				AND IID = @IID ) 
        BEGIN
            UPDATE  [Invoice]
            SET     [Name] = @Name ,
                    [Amount] = @Amount ,
                    [Title] = @Title ,
                    [Tel] = @Tel ,
                    [Province] = @Province ,
                    [City] = @City ,
                    [Address] = @Address ,
                    [Zip] = @Zip
            WHERE   MID = @MID
			AND IID = @IID
        END
    ELSE 
        BEGIN
            DECLARE @SID INT
            EXECUTE [Get_Seed] @ID = 3, @Value = @SID OUTPUT
            SET @IID = @SID

            

            INSERT  INTO [Invoice]
                    ( [IID] ,
                      [MID] ,
                      [IDSN] ,
                      [Name] ,
                      [Amount] ,
                      [Title] ,
                      [Tel] ,
                      [Province] ,
                      [City] ,
                      [Address] ,
                      [Zip],
					  [CreateDate]
                    )
            VALUES  ( @IID ,
                      @MID ,
                      @IDSN ,
                      @Name ,
                      @Amount ,
                      @Title ,
                      @Tel ,
                      @Province ,
                      @City ,
                      @Address ,
                      @Zip,
					  GETDATE()
                    )
        END
    RETURN 0