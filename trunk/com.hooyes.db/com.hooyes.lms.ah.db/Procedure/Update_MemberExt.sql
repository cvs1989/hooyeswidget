-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2013-12-18
-- Update date: 2013-12-18
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Update_MemberExt]
    @MID INT ,
    @IDSN VARCHAR(30) , -- Primary Key
    @Year INT ,
    @Flag INT
AS 
    UPDATE  MemberExt
    SET     [Year] = @Year ,
            Flag = @Flag
    WHERE   IDSN = @IDSN
    IF @@ROWCOUNT = 0 AND @MID > 0 
        BEGIN 
            INSERT  INTO [dbo].[MemberExt]
                    ( [MID], [IDSN], [Year], [Flag] )
            VALUES  ( @MID, @IDSN, @Year, @Flag )
        END

    RETURN 0
