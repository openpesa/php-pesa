---
id: stored_session
title: Stored session
---

<!-- ## 🚧 WIP -->

:::caution

## Warning! 🚧 WIP!

:::

## Mpesa OpenAPI solutions

Before you can integrate on the M-Pesa OpenAPI solution, you must exchange your Application Key for a Session Key. The API Key is created with the creation of a new application. The Session Key acts as an access token that authorises the rest of your REST API calls to the system. A `valid Session Key` is needed to transact on M-Pesa using OpenAPI.

A single Session can be valid from minutes, hours and even days

One can leverage this and save a session for later use privied the lifetime has not expired.

## Session API

-   `generate_key` Generate a new session id
