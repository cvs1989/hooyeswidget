-- DROP PROC [Get_Member]
GO
-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2011-12-18
-- Update date: 2014-05-22
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_Member] @MID INT = 0
AS 
    SELECT  a.* ,
            RegionName = b.Name
    FROM    dbo.Member a
            LEFT JOIN dbo.Region b ON a.RegionCode = b.Code
    WHERE   a.MID = @MID
    RETURN 0