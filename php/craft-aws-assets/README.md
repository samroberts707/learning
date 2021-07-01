# CraftCMS with AWS Assets

This is a trial install of CraftCMS that uses AWS S3 for storing assets and AWS CloudFront to serve the assets.

I'm using this tutorial as a guide but will have step by step notes below as an updated guide: https://nystudio107.com/blog/using-aws-s3-buckets-cloudfront-distribution-with-craft-cms#setting-up-s3

### Step One - Create S3 Bucket

Create an AWS S3 Bucket. For this example I'll be setting up the bucket called `nucreative-craft-test`.
Buckets should be unique to each project and follow the same naming convention, this will be as follows moving forward `PROJECT_NAME-bucket`.

When creating the bucket leave all options as default except the name and be sure to make sure **Block *all* public access** is selected. (This will be automatically updated when creating CloudFront Distribution)

### Step Two - Create IAM
IAM stands for *Identity and Access Management* this in short controls who can do what in a AWS object, such as an S3 Bucket.

We want to create a new IAM Policy and User for each client website so they can only access and edit their own content.

To do this go to Services -> IAM -> Policies -> **Create Policy**.

Click the JSON tab and paste in the following, being sure to replace `REPLACE-WITH-BUCKET-NAME` with the bucket name we just created.

<details>
<summary>JSON</summary>
<br>
<pre>
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Effect": "Allow",
            "Action": [
                "acm:ListCertificates",
                "cloudfront:GetDistribution",
                "cloudfront:GetStreamingDistribution",
                "cloudfront:GetDistributionConfig",
                "cloudfront:ListDistributions",
                "cloudfront:ListCloudFrontOriginAccessIdentities",
                "cloudfront:CreateInvalidation",
                "cloudfront:GetInvalidation",
                "cloudfront:ListInvalidations",
                "elasticloadbalancing:DescribeLoadBalancers",
                "iam:ListServerCertificates",
                "sns:ListSubscriptionsByTopic",
                "sns:ListTopics",
                "waf:GetWebACL",
                "waf:ListWebACLs"
            ],
            "Resource": "*"
        },
        {
            "Effect": "Allow",
            "Action": [
                "s3:GetBucketLocation",
                "s3:ListAllMyBuckets"
            ],
            "Resource": "*"
        },
        {
            "Effect": "Allow",
            "Action": [
                "s3:ListBucket"
            ],
            "Resource": [
                "arn:aws:s3:::REPLACE-WITH-BUCKET-NAME"
            ]
        },
        {
            "Effect": "Allow",
            "Action": [
                "s3:*"
            ],
            "Resource": [
                "arn:aws:s3:::REPLACE-WITH-BUCKET-NAME/*"
            ]
        }
    ]
}
</pre>
</details>

Once copied in and the bucket name replaced click through to **Tags** and then to **Review**. Here we create the policy name, I will be following this naming convention moving forward `PROJECT_NAME-policy`. Add a brief description and create the policy.


### Step Three - Create a IAM Group

Rather than setting the new policy we just made to a bucket or user we're going to attach it to a user group. This adds a bit of fallback in case a user credentials get lost. 

To do this navigatate to Services -> IAM -> User Groups > **Create Group**

Name the group using the following naming convention `PROJECT_NAME-group` and attach the policy we just created to the group. *For now don't add any users*.

### Step Four - Create a IAM User

Next we're going to create a user for this project, this user will give access to the S3 Bucket so we will need to remember its **Access Key ID** and **Secret access Key**. (*These are best stored in 1password, along with the CSV download*)

Create the user following this naming convention `PROJECT_NAME-user`, give it the **Programatic access** type. Then on the next screen tick the user group we just created. 

Once you create the user you will now be given these Access Keys, **THIS IS THE ONLY TIME TO GET THESE** so make sure to securely save them and the CSV download.

### Step Five - Create a CloudFront Distribution
Here we will create the CloudFront Distribution that will act as our CDN to deliver assets to our end-users. This is the only way to publically access the assets on the S3 Bucket.

To create a new Distribution navigate to the following Services -> CloudFront -> **Create Distribution**

Use the following settings when creating a new distribution:

- **Origin Domain Name** -- Select the S3 Bucket created in Step One
- **Restrict Bucket Access** -- Yes
- **Origin Access Identity** -- Create a New Identity
- **Grant Read Permissions on Bucket** -- Yes, Update Bucket Policy
- **Viewer Protocol Policy** -- Redirect HTTP to HTTPS
- **Allowed HTTP Methods** -- GET, HEAD, OPTIONS, PUT, POST, PATCH, DELETE
- **Compress Objects Automatically** -- Yes

Please note that the domain name needs to [conform to DNS naming requirements](https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/DownloadDistS3AndCustomOrigins.html#concept_S3Origin).

Once all the settings are correct you can create the new Distribution. You'll be taken to a fairly blank "Getting Started" page, this isn't an error. 

Next we need to get in to the Distribution we just created by going to Services -> CloudFront -> NEW_SERVICE_NAME

Here we need to save the `Distribution ID` and the `Domain Name` these are important when setting up a volume in Craft.

### Step Six - Creating an asset volume in Craft

Lastly we need to setup a new Asset Volume in Craft so we can use all the things we just setup in AWS.

Before we do anything else we need to install the first-party [Amazon S3 plugin](https://plugins.craftcms.com/aws-s3).


Now we have the plugin installed we should set all our AWS variables in our `.env` file. These should be kept a secret at all times so shouldn't be in the database or Git.

<details>
<summary>.env variables</summary>
<pre>
#S3 settings
S3_KEY_ID=XXXXXXXXXX
S3_SECRET=XXXXXXXXXX
S3_BUCKET=PROJECT_NAME-bucket
S3_REGION=eu-west-2

#CloudFront settings
CLOUDFRONT_URL=https://XXXXXXXX.cloudfront.net
CLOUDFRONT_DISTRIBUTION_ID=XXXXXXXXXXXX
CLOUDFRONT_PATH_PREFIX=
</pre>
</details>

Once done we can create a new volume in the dashboard. Go to Settings -> Assets -> Volumes -> **New Volume**

The volume should follow these settings:

- **Name** -- PROJECT_NAME-aws
- **Assets in this volume have public URLs** -- Yes
- **Base URL** -- `$CLOUDFRONT_URL`
- **Volume Type** -- Amazon S3
- **Access Key ID** -- `$S3_KEY_ID`
- **Secret Access Key** -- `$S3_SECRET`
- **Bucket** -- Manual // `$S3_BUCKET` // `$S3_REGION`
- **Add the subfolder to the Base URL?** -- No
- **Make Uploads Public** -- No
- **Cache Duration** -- 3 Months
- **CloudFront Distribution ID** -- `$CLOUDFRONT_DISTRIBUTION_ID`
- **CloudFront Path Prefix** -- `$CLOUDFRONT_PATH_PREFIX`


That should be everything setup and working. Please note that AWS can take some time with updating policies to make assets viewable through the Distributer. 

SideNote -- The first time I did this I had to manually change the S3 Bucket Public Access, I kept ACLs blocked but allowed access through policies.