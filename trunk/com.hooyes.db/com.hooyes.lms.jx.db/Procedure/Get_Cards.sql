-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2013-09-15
-- Update date: 2013-09-15
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_Cards]
    @SN VARCHAR(100)
AS 
    SELECT  *
    FROM    Cards
    WHERE   SN = @SN
    RETURN 0
