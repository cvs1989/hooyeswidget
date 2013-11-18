-- DROP PROC [Get_Invoice]
-- =============================================
-- Version:     1.0.0.2
-- Author:		hooyes
-- Create date: 2012-02-22
-- Update date: 2013-10-03
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_Invoice] 
    @MID INT = 0, 
	@IID INT = 0
AS 
    SELECT  [ID] ,
            [IID] ,
            [MID] ,
            [IDSN] ,
            [Name] ,
            [Amount] ,
            [Title] ,
            [Tel] ,
            [Province] ,
            [City] ,
            [Address] ,
            [Zip] ,
            [CreateDate]
    FROM    [dbo].[Invoice]
    WHERE   MID = @MID
            AND ( @IID = 0
                  OR IID = @IID
                )
    ORDER BY IID DESC
    RETURN 0