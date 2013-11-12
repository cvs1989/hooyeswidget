-- =============================================
-- Version:     1.0.0.2
-- Author:		hooyes
-- Create date: 2013-11-08
-- Update date: 2013-11-12
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Update_Survey]
    @Cate INT ,
    @Name VARCHAR(50) = NULL ,
    @IDCard VARCHAR(20) = NULL ,
    @IDSN VARCHAR(20) = NULL ,
	@IDCert VARCHAR(30) = NULL ,
    @Phone VARCHAR(20) = NULL ,
	@IP VARCHAR(20) = NULL,
    @Comment NVARCHAR(4000) =NULL
	
AS 
    INSERT  INTO [Survey]
            ( [Cate] ,
              [Name] ,
              [IDCard] ,
              [IDSN] ,
			  [IDCert],
              [Phone] ,
			  [IP],
              [Comment],
			  [CreateDate]
            )
    VALUES  ( @Cate ,
              @Name ,
              @IDCard ,
              @IDSN ,
			  @IDCert,
              @Phone ,
			  @IP,
              @Comment,
			  GETDATE()
            )
    RETURN 0
